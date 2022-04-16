import React from "react";

function Spinner()
{
    return (
        <div className="d-flex align-items-center text-secondary">
            <strong>Loading...</strong>
            <div className="spinner-border ms-auto" role="status" aria-hidden="true" />
        </div>
    );
}

export default Spinner;