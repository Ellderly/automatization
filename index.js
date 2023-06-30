const express = require('express');
const multer = require('multer');
const fs = require('fs');
const path = require('path');
const cheerio = require('cheerio');

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

    const indexPath = filePaths.find(filePath => /(index\.html|index\.php)$/.test(filePath));
    processFiles(indexPath, filePaths);
    res.download(indexPath, path.basename(indexPath), function (err) {
        if (err) {
            console.error(err);
        } else {
            fs.unlinkSync(indexPath);
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

function uncommentPhp(code) {
    const phpCommentRegex = /<!--\?php(.+?)\?-->/gs;
    return code.replace(phpCommentRegex, '<?php$1?>');
}

function processFiles(indexPath, filePaths) {
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

    let html = $.html();

    // Uncomment PHP code
    html = uncommentPhp(html);

    fs.writeFileSync(indexPath, html);

    // удаление всех файлов, кроме index.html и использованных изображений
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
