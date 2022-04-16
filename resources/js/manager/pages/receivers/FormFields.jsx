export default function FormFields()
{
    return {
        receiver: [
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
                    name: "receiver[id]",
                    type: "text",
                    className: "form-control",
                    placeholder: "ID",
                    disabled: "disabled"
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver[first_name]",
                    className: "form-label",
                    text:  "First name"
                },
                inputElement: {
                    id: "receiver[first_name]",
                    name: "receiver[first_name]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a first name..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver[last_name]",
                    className: "form-label",
                    text:  "Last name"
                },
                inputElement: {
                    id: "receiver[last_name]",
                    name: "receiver[last_name]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a last name..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver[email]",
                    className: "form-label",
                    text:  "Email"
                },
                inputElement: {
                    id: "receiver[email]",
                    name: "receiver[email]",
                    type: "email",
                    className: "form-control",
                    placeholder: "Type an email..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver[password]",
                    className: "form-label",
                    text:  "Password"
                },
                inputElement: {
                    id: "receiver[password]",
                    name: "receiver[password]",
                    type: "password",
                    className: "form-control",
                    placeholder: "Set a password..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    title: "Status",
                    titleClassName: "fw-bold mb-2",
                    className: "form-check",
                },
                labelElement: {
                    htmlFor: "receiver[status]-active",
                    className: "form-check-label",
                    text:  "Active"
                },
                inputElement: {
                    id: "receiver[status]-active",
                    name: "receiver[status]",
                    type: "radio",
                    className: "form-check-input",
                },
                beginLayoutWith: "input",
                value: "Active",
                checked: false,
            },
            {
                wrapperElement: {
                    className: "form-check",
                },
                labelElement: {
                    htmlFor: "receiver[status]-blocked",
                    className: "form-check-label",
                    text:  "Blocked"
                },
                inputElement: {
                    id: "receiver[status]-blocked",
                    name: "receiver[status]",
                    type: "radio",
                    className: "form-check-input"
                },
                beginLayoutWith: "input",
                value: "Blocked",
                checked: false,
            },
            {
                wrapperElement: {
                    className: "form-check",
                },
                labelElement: {
                    htmlFor: "receiver[status]-not-verified",
                    className: "form-check-label",
                    text:  "Not Verified"
                },
                inputElement: {
                    id: "receiver[status]-not-verified",
                    name: "receiver[status]",
                    type: "radio",
                    className: "form-check-input"
                },
                hintElement: {
                    content: "The default value is 'Not verified'",
                    className: "text-muted",
                },
                beginLayoutWith: "input",
                value: "Not verified",
                checked: false,
            },
        ],
        receiver_data: [
            { // ID
                wrapperElement: {
                    className: "mb-2",
                },
                inputElement: {
                    name: "receiver_data[id]",
                    type: "hidden"
                },
            },
            {
                wrapperElement: {
                    className: "form-check form-switch mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[is_kyc_passed]",
                    className: "form-check-label",
                    text:  "Is KYC passed?"
                },
                inputElement: {
                    id: "receiver_data[is_kyc_passed]",
                    name: "receiver_data[is_kyc_passed]",
                    type: "checkbox",
                    className: "form-check-input",
                    placeholder: ""
                },
                beginLayoutWith: "input",
                value: "",
                checked: false,
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[phone_number]",
                    className: "form-label",
                    text:  "Phone number"
                },
                inputElement: {
                    id: "receiver_data[phone_number]",
                    name: "receiver_data[phone_number]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a phone number..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[ssn]",
                    className: "form-label",
                    text:  "The last 4 digits of the SSN"
                },
                inputElement: {
                    id: "receiver_data[ssn]",
                    name: "receiver_data[ssn]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a SSN numbers..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[birth_date]",
                    className: "form-label",
                    text:  "Birth Date"
                },
                inputElement: {
                    id: "receiver_data[birth_date]",
                    name: "receiver_data[birth_date]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Choose a birth date..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[address]",
                    className: "form-label",
                    text:  "Address"
                },
                inputElement: {
                    id: "receiver_data[address]",
                    name: "receiver_data[address]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type an address..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[address_2]",
                    className: "form-label",
                    text:  "Second address"
                },
                inputElement: {
                    id: "receiver_data[address_2]",
                    name: "receiver_data[address_2]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a second address..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[postal_code]",
                    className: "form-label",
                    text:  "Postal code"
                },
                inputElement: {
                    id: "receiver_data[postal_code]",
                    name: "receiver_data[postal_code]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a postal code..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[city]",
                    className: "form-label",
                    text:  "City"
                },
                inputElement: {
                    id: "receiver_data[city]",
                    name: "receiver_data[city]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a city..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[state]",
                    className: "form-label",
                    text:  "State"
                },
                inputElement: {
                    id: "receiver_data[state]",
                    name: "receiver_data[state]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a state..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "receiver_data[country]",
                    className: "form-label",
                    text:  "Country"
                },
                inputElement: {
                    id: "receiver_data[country]",
                    name: "receiver_data[country]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a country..."
                },
                value: ""
            },
        ],
        credit_card: [
            { // ID
                wrapperElement: {
                    className: "mb-2",
                },
                inputElement: {
                    name: "credit_card[id]",
                    type: "hidden"
                },
                visible: false,
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "credit_card[number]",
                    className: "form-label",
                    text:  "Number"
                },
                inputElement: {
                    id: "credit_card[number]",
                    name: "credit_card[number]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a credit card number..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "credit_card[expired_date]",
                    className: "form-label",
                    text:  "Expired date"
                },
                inputElement: {
                    id: "credit_card[expired_date]",
                    name: "credit_card[expired_date]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a date..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "credit_card[secret_code]",
                    className: "form-label",
                    text:  "Secret code (CVV/CVC)"
                },
                inputElement: {
                    id: "credit_card[secret_code]",
                    name: "credit_card[secret_code]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a secret code..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "credit_card[zip_code]",
                    className: "form-label",
                    text:  "ZIP code"
                },
                inputElement: {
                    id: "credit_card[zip_code]",
                    name: "credit_card[zip_code]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a zip code..."
                },
                value: ""
            },
        ],
        bank_account: [
            { // ID
                wrapperElement: {
                    className: "mb-2",
                },
                inputElement: {
                    name: "bank_account[id]",
                    type: "hidden"
                },
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "bank_account[bank_code]",
                    className: "form-label",
                    text:  "Bank code"
                },
                inputElement: {
                    id: "bank_account[bank_code]",
                    name: "bank_account[bank_code]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a bank code..."
                },
                value: ""
            },
            {
                wrapperElement: {
                    className: "mb-3",
                },
                labelElement: {
                    htmlFor: "bank_account[account_number]",
                    className: "form-label",
                    text:  "Account number"
                },
                inputElement: {
                    id: "bank_account[account_number]",
                    name: "bank_account[account_number]",
                    type: "text",
                    className: "form-control",
                    placeholder: "Type a account number..."
                },
                value: ""
            },
        ],
    };
}
