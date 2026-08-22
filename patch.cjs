const fs = require('fs');
let content = fs.readFileSync('public/js/microsite-editor.js', 'utf8');

function addScroll(content, funcName, formBodyVar) {
    const regex = new RegExp(`(function ${funcName}\\([^{]*{[\\s\\S]*?if \\(btnText\\) btnText\\.innerText = 'Tutup';\\s*)}(\\s*\\n)`, 'g');
    return content.replace(regex, `$1    setTimeout(() => {
            const block = ${formBodyVar}.closest('.draggable-element-block') || ${formBodyVar}.closest('.profile-block-wrapper') || ${formBodyVar};
            if (block) {
                block.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 300);
}$2`);
}

content = addScroll(content, 'toggleProfileEditForm', 'formBody');
content = addScroll(content, 'toggleImageEditForm', 'formBody');
content = addScroll(content, 'toggleDividerEditForm', 'formBody');
content = addScroll(content, 'toggleTextEditForm', 'formBody');

fs.writeFileSync('public/js/microsite-editor.js', content);
