const fs = require('fs');
const sass = require('sass');

function scopeCssFile(inputPath, outputPath, scopeClass) {
    if (!fs.existsSync(inputPath)) return;
    const css = fs.readFileSync(inputPath, 'utf8');
    const scss = `.${scopeClass} {\n${css}\n}`;
    const result = sass.compileString(scss);
    fs.writeFileSync(outputPath, result.css);
    console.log(`Scoped ${inputPath} to ${outputPath} under .${scopeClass}`);
}

scopeCssFile('public/css/pages/shortlink-create.css', 'public/css/pages/shortlink-create.css', 'page-shortlink-create');
scopeCssFile('public/css/pages/shortlink-analytics.css', 'public/css/pages/shortlink-analytics.css', 'page-shortlink-analytics');
scopeCssFile('public/css/pages/orders.css', 'public/css/pages/orders.css', 'page-orders-view');
