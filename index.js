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
    const phpCommentRegex = /<!--\?(.*?)\?-->/gs;
    return code.replace(phpCommentRegex, (match, p1) => {
        let result = '<?' + p1.replace("/-->", "") + '?>';
        return result;
    });
}
function fixHeadContent(html) {
    const $ = cheerio.load(html, {
        decodeEntities: false
    });

    const headContent = ['base', 'meta', 'title', 'link', 'style', 'script'];
    headContent.forEach(tagName => {
        $(tagName).each(function () {
            if($(this).parent().is('body')) {
                $('head').append($(this));
            }
        });
    });

    return $.html();
}


function processFiles(indexPath, filePaths) {
    const $ = cheerio.load(fs.readFileSync(indexPath, 'utf-8'), {
        decodeEntities: false
    });

    $('link[rel="stylesheet"]').each(function () {
        const content = replaceContentWithFile('uploads/', $(this).attr('href'));
        if (content) {
            $('head').append('<style>\n' + content + '\n</style>');
            $(this).remove();
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
        const content = replaceContentWithFile('uploads/', $(this).attr('src'));
        if (content) {
            $('head').append('<script>\n' + content + '\n</script>');
            $(this).remove();
        }
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
    // html = uncommentPhp(html);

    html = fixHeadContent(html);

    fs.writeFileSync(indexPath, html);

    // удаление всех файлов, кроме index.html и использованных изображений
    filePaths.forEach(filePath => {
        if (filePath !== indexPath && !imgFilePaths.includes(filePath) && fs.existsSync(filePath)) {
            fs.unlinkSync(filePath);
        }
    });

}

function replaceContentWithFile(basePath, relativePath) {
    const fullPath = path.join(basePath, path.basename(relativePath));
    if (fs.existsSync(fullPath)) {
        const content = fs.readFileSync(fullPath, 'utf-8');
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
