export default function FormFields()
{
    return [
        {
            wrapperElement: {
                className: "mb-3",
            },
            labelElement: {
                htmlFor: "id",
                className: "form-label",
                text:  "ID"
            },
            inputElement: {
                id: "id",
                name: "id",
                type: "text",
                className: "form-control",
                placeholder: "ID",
                disabled: "disabled"
            },
            value: ""
        }, {
            wrapperElement: {
                className: "mb-3",
            },
            labelElement: {
                htmlFor: "first-name",
                className: "form-label",
                text:  "First Name"
            },
            inputElement: {
                id: "first-name",
                name: "first_name",
                type: "text",
                className: "form-control",
                placeholder: "Type a first name..."
            },
            value: ""
        }, {
            wrapperElement: {
                className: "mb-3",
            },
            labelElement: {
                htmlFor: "last-name",
                className: "form-label",
                text:  "Last Name"
            },
            inputElement: {
                id: "last-name",
                name: "last_name",
                type: "text",
                className: "form-control",
                placeholder: "Type a last name..."
            },
            value: ""
        }, {
            wrapperElement: {
                className: "mb-3",
            },
            labelElement: {
                htmlFor: "email",
                className: "form-label",
                text:  "Email"
            },
            inputElement: {
                id: "email",
                name: "email",
                type: "email",
                className: "form-control",
                placeholder: "Type an email..."
            },
            value: ""
        }, {
            wrapperElement: {
                className: "mb-3",
            },
            labelElement: {
                htmlFor: "password",
                className: "form-label",
                text:  "Password"
            },
            inputElement: {
                id: "password",
                name: "password",
                type: "password",
                className: "form-control",
                placeholder: "Set a password..."
            },
            value: ""
        },
        // {
        //     wrapperElement: {
        //         className: "form-check form-switch my-4",
        //     },
        //     labelElement: {
        //         htmlFor: "status",
        //         className: "form-check-label",
        //         text:  "Does the user has the approved email?"
        //     },
        //     inputElement: {
        //         id: "status",
        //         name: "status",
        //         type: "checkbox",
        //         className: "form-check-input"
        //     },
        //     beginLayoutWith: "input",
        //     value: "Pending"
        // },
    ];
}
