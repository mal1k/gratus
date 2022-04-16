import { Link, useLocation } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

function noMatch() {
    let location = useLocation();

    return (
        <>
            <section className="content-header">
                {/* 
                This section should be here for expansion of background and the entire layout 
                */}
            </section>
            <section className="content">
                <div className="error-page">
                    <h2 className="headline text-warning">404</h2>

                    <div className="error-content">
                        <h3>
                            <FontAwesomeIcon 
                                className="text-warning"
                                icon={ ["fas", "exclamation-triangle"] }
                                size="1x" />
                            Page not found.
                        </h3>

                        <p>
                            We could not find the page <code>{ location.pathname }</code> that you looking for.
                            Meanwhile, you may <Link to="/manager/dashboard">return to the dashboard</Link>.
                        </p>
                    </div>
                </div>
            </section>
        </>
    );
}

export default noMatch;