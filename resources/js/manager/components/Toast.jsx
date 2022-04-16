import React from "react";

function Toast( props )
{
    let time = () => {
        return "just now";
    };

    let handleClick = ( e ) => {
        let self = e.currentTarget;
        self.classList.remove("show");
    };

    return (
        <div ref={ props.forwardRef } className="toast-wrap">
            <div 
                className="toast fade"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
                onClick={ handleClick }
            >
                <div className="toast-header bg-light">
                    <span className="d-block me-auto">
                        <span className="pe-2">{ props.faAwesome }</span>
                        <small>{ time() }</small>
                    </span>
                    <button type="button" className="btn-close float-end" aria-label="Close"></button>
                </div>
                <div className={ "toast-body " + props.bodyStyle }>
                    { props.message }
                </div>
            </div>
        </div>
    );
}

export default React.forwardRef((props, ref) => {
    return <Toast forwardRef={ ref } { ...props } />
});