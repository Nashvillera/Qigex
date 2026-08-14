import fs from 'fs';
import path from 'path';

const targetDir = path.join(process.cwd(), 'haivora-logistics');

if (!fs.existsSync(targetDir)) {
    fs.mkdirSync(targetDir, { recursive: true });
}

// Copy all root PHP files, CSS, PNG, MD files
const rootFiles = fs.readdirSync(process.cwd()).filter(file => {
    return file.endsWith('.php') || file === 'style.css' || file === 'screenshot.png' || file === 'README.md';
});

rootFiles.forEach(file => {
    const src = path.join(process.cwd(), file);
    const dest = path.join(targetDir, file);
    if (fs.existsSync(src)) {
        fs.copyFileSync(src, dest);
    }
});

function copyDirRecursive(srcDir, destDir) {
    if (!fs.existsSync(srcDir)) return;
    if (!fs.existsSync(destDir)) fs.mkdirSync(destDir, { recursive: true });

    const entries = fs.readdirSync(srcDir, { withFileTypes: true });
    for (let entry of entries) {
        const srcPath = path.join(srcDir, entry.name);
        const destPath = path.join(destDir, entry.name);

        if (entry.isDirectory()) {
            copyDirRecursive(srcPath, destPath);
        } else {
            fs.copyFileSync(srcPath, destPath);
        }
    }
}

copyDirRecursive(path.join(process.cwd(), 'inc'), path.join(targetDir, 'inc'));
copyDirRecursive(path.join(process.cwd(), 'assets'), path.join(targetDir, 'assets'));

console.log('Successfully synchronized all haivora-logistics theme files.');
