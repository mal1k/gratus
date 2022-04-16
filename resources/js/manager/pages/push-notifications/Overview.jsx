export default function OverviewFields()
{
    return [
        { // ID
            wrapperElement: {
                className: "mb-2",
            },
            spanElement: {
                className: "text-secondary"
            },
            inputElement: {
                name: "id",
                type: "hidden"
            },
        }, {
            wrapperElement: {
                className: "mb-2",
            },
            spanElement: {
                className: "text-secondary"
            },
            inputElement: {
                name: "title",
                type: "hidden"
            },
        }, {
            wrapperElement: {
                className: "mb-2",
            },
            spanElement: {
                className: "text-secondary"
            },
            inputElement: {
                name: "content",
                type: "hidden"
            },
        },
    ];
}
