function execCmd(id, command, value = null) {
    const editor = document.getElementById('editorContent_' + id);
    if (editor) {
        editor.focus();
        
        // Ensure there is a selection. If the selection is empty, select all text in the editor.
        const selection = window.getSelection();
        if (selection.toString().length === 0) {
            document.execCommand('selectAll', false, null);
        }
        
        document.execCommand(command, false, value);
        updateTextPreview(id);
    }
}
