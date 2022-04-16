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
                htmlFor: "title",
                className: "form-label",
                text:  "Title"
            },
            inputElement: {
                id: "title",
                name: "title",
                type: "text",
                className: "form-control",
                placeholder: "Type a title..."
            },
            value: ""
        }, {
            wrapperElement: {
                className: "mb-3",
            },
            labelElement: {
                htmlFor: "content",
                className: "form-label",
                text:  "Content"
            },
            inputElement: {
                id: "content",
                name: "content",
                type: "text",
                className: "form-control",
                placeholder: "Type a content..."
            },
            value: ""
        },
    ];
}
