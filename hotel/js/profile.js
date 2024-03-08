// script.js

function toggleEditField(fieldId) {
    var field = document.getElementById(fieldId);
    var editBtn = document.getElementById(fieldId + '-edit');
    var saveBtn = document.getElementById(fieldId + '-save');
    var cancelBtn = document.getElementById(fieldId + '-cancel');

    field.readOnly = !field.readOnly;
    field.classList.toggle('editable');

    if (field.readOnly) {
        // Mode lecture seule
        editBtn.style.display = 'inline';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
    } else {
        // Mode édition
        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline';
        cancelBtn.style.display = 'inline';
    }
}

function cancelEdit(fieldId) {
    var field = document.getElementById(fieldId);
    field.readOnly = true;
    field.classList.remove('editable');

    var editBtn = document.getElementById(fieldId + '-edit');
    var saveBtn = document.getElementById(fieldId + '-save');
    var cancelBtn = document.getElementById(fieldId + '-cancel');

    editBtn.style.display = 'inline';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
}
