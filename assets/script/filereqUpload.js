       // Document configurations
       const documentConfigs = {
        birth_cert: {
            name: "Birth Certificate",
            fields: [
                {
                    type: "pdf",
                    label: "Birth Certificate (PDF)",
                    required: true
                }
            ]
        },
        sf10: {
            name: "Transcript of Records (SF10)",
            note: "(if transferee)",
            fields: [
                {
                    type: "pdf",
                    label: "SF10 Document (PDF)",
                    required: true
                },
                {
                    type: "excel",
                    label: "SF10 Document (Excel)",
                    required: true
                }
            ]
        }
        // Add more document types here as needed
        // Example:
        // graduation_cert: {
        //     name: "Graduation Certificate",
        //     fields: [
        //         {
        //             type: "pdf",
        //             label: "Graduation Certificate (PDF)",
        //             required: true
        //         }
        //     ]
        // }
    };

    // Function to generate table rows
    function generateDocumentRows() {
        const tbody = document.querySelector('.table tbody');
        tbody.innerHTML = '';

        Object.entries(documentConfigs).forEach(([docType, config]) => {
            const tr = document.createElement('tr');
            const docName = config.note ? 
                `${config.name} <i>${config.note}</i>` : 
                config.name;

            tr.innerHTML = `
                <td>${docName}</td>
                <td>No uploaded file yet</td>
                <td>
                    <button type="button" class="btn btn-primary" onclick="openUploadModal('${docType}', 'upload')">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Function to open modal
    function openUploadModal(documentType, action) {
        const config = documentConfigs[documentType];
        if (!config) return;

        const dynamicFields = document.getElementById('dynamicUploadFields');
        const modalTitle = document.getElementById('uploadModalLabel');
        const submitBtn = document.getElementById('modalSubmitBtn');
        const modal = new bootstrap.Modal(document.getElementById('uploadModal'));

        // Clear previous fields
        dynamicFields.innerHTML = '';

        switch(action) {
            case 'upload':
                modalTitle.textContent = `Upload ${config.name}`;
                submitBtn.textContent = 'Upload';
                submitBtn.style.display = 'block';

                // Generate fields based on configuration
                config.fields.forEach((field, index) => {
                    const fieldId = `${documentType}_${field.type}_${index}`;
                    const acceptTypes = field.type === 'pdf' ? '.pdf' : 
                                    field.type === 'excel' ? '.xlsx,.xls' : 
                                    '*';

                    dynamicFields.innerHTML += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">${field.label}</label>
                            <input type="file" 
                                class="form-control" 
                                id="${fieldId}" 
                                accept="${acceptTypes}"
                                ${field.required ? 'required' : ''}>
                        </div>
                    `;
                });
                break;

            case 'view':
                modalTitle.textContent = `View ${config.name}`;
                submitBtn.style.display = 'none';
                
                config.fields.forEach(field => {
                    dynamicFields.innerHTML += `
                        <div class="mb-3">
                            <h6>${field.label}</h6>
                            <div class="border p-3">
                                [${field.type.toUpperCase()} Preview Placeholder]
                            </div>
                        </div>
                    `;
                });
                break;

            case 'edit':
                modalTitle.textContent = `Edit ${config.name}`;
                submitBtn.textContent = 'Save Changes';
                submitBtn.style.display = 'block';

                config.fields.forEach((field, index) => {
                    const fieldId = `${documentType}_${field.type}_${index}_edit`;
                    const acceptTypes = field.type === 'pdf' ? '.pdf' : 
                                    field.type === 'excel' ? '.xlsx,.xls' : 
                                    '*';

                    dynamicFields.innerHTML += `
                        <div class="mb-3">
                            <label for="${fieldId}" class="form-label">Replace ${field.label}</label>
                            <input type="file" 
                                class="form-control" 
                                id="${fieldId}" 
                                accept="${acceptTypes}">
                        </div>
                    `;
                });
                break;

            case 'download':
                modalTitle.textContent = `Download ${config.name}`;
                submitBtn.style.display = 'none';

                config.fields.forEach(field => {
                    dynamicFields.innerHTML += `
                        <button class="btn btn-primary w-100 mb-2">
                            Download ${field.label}
                        </button>
                    `;
                });
                break;
        }

        modal.show();
    }