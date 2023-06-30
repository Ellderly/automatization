const express = require('express');
const multer = require('multer');
const fs = require('fs');
const path = require('path');
const cheerio = require('cheerio');
const child_process = require('child_process');

const app = express();
const upload = multer({ dest: 'uploads/' });

const PORT = process.env.PORT || 3000;

app.use(express.static('public'));

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'index.html'));
});

app.post('/upload', upload.array('files'), (req, res) => {
    let filePaths = [];
    req.files.forEach(file => {
        const ext = path.extname(file.originalname);
        const newPath = path.join(file.destination, file.originalname);
        fs.renameSync(file.path, newPath);
        filePaths.push(newPath);
    });

    filePaths = flattenDirectories(filePaths);

    const indexFilePath = filePaths.find(filePath => filePath.endsWith('index.html') || filePath.endsWith('index.php'));
    if (!indexFilePath) {
        res.status(400).send('index.html or index.php is not found in the uploaded files.');
        return;
    }

    if (indexFilePath.endsWith('.php')) {
        const output = child_process.execSync(`php ${indexFilePath}`);
        fs.writeFileSync(indexFilePath.replace('.php', '.html'), output);
    }

    processFiles(indexFilePath.replace('.php', '.html'), filePaths);

    res.download(indexFilePath.replace('.php', '.html'), 'index.html', function (err) {
        if (err) {
            console.error(err);
        } else {
            fs.unlinkSync(indexFilePath.replace('.php', '.html'));
        }
    });
});

function flattenDirectories(filePaths) {
    return filePaths.map(filePath => {
        const fileName = path.basename(filePath);
        const newFilePath = path.join('uploads/', fileName);
        fs.renameSync(filePath, newFilePath);
        return newFilePath;
    });
}

function processFiles(indexPath, filePaths) {
    filePaths.forEach(filePath => {
        if (filePath.endsWith('.php')) {
            const phpOutput = child_process.execSync(`php ${filePath}`);
            fs.writeFileSync(filePath.replace('.php', '.html'), phpOutput);
        }
    });

    const $ = cheerio.load(fs.readFileSync(indexPath, 'utf-8'));

    $('link[rel="stylesheet"]').each(function () {
        let content = replaceContentWithFile('uploads/', $(this).attr('href'), $(this), `<style>\n%s\n</style>`);
        if (content) {
            $(this).replaceWith(`<style>\n${content}\n</style>`);
        }
    });

    $('style').each(function () {
        let styleContent = $(this).html();
        const urlRegex = /url\(["']?(.*?)["']?\)/g;
        let match;
        while ((match = urlRegex.exec(styleContent)) !== null) {
            const imagePath = match[1];
            const base64Image = replaceImageWithBase64('uploads/', imagePath);
            if (base64Image) {
                console.log(`Found image in CSS: ${imagePath}`);
                styleContent = styleContent.replace(imagePath, base64Image);
            }
        }
        $(this).html(styleContent);
    });

    $('script[src]').each(function () {
        replaceContentWithFile('uploads/', $(this).attr('src'), $(this), `<script>\n%s\n</script>`);
    });

    let imgFilePaths = [];
    $('img[src]').each(function () {
        let imgPath = $(this).attr('src');
        const base64Image = replaceImageWithBase64('uploads/', imgPath);
        if (base64Image) {
            $(this).attr('src', base64Image);
            imgFilePaths.push(path.join('uploads/', path.basename(imgPath)));
        }
    });

    fs.writeFileSync(indexPath, $.html());

    filePaths.forEach(filePath => {
        if (filePath !== indexPath && !imgFilePaths.includes(filePath)) {
            fs.unlinkSync(filePath);
        }
    });
}

function replaceContentWithFile(basePath, relativePath, element, template) {
    const fullPath = path.join(basePath, path.basename(relativePath));
    if (fs.existsSync(fullPath)) {
        const content = fs.readFileSync(fullPath, 'utf-8');
        element.replaceWith(template.replace('%s', content));
        return content;
    }
    return null;
}

function replaceImageWithBase64(basePath, relativePath) {
    const fullPath = path.join(basePath, path.basename(relativePath));
    if (fs.existsSync(fullPath)) {
        const imageBuffer = fs.readFileSync(fullPath);
        const base64Image = "data:image/jpg;base64," + Buffer.from(imageBuffer).toString('base64');
        return base64Image;
    }
    return null;
}

app.listen(PORT, () => console.log('Server started'));
