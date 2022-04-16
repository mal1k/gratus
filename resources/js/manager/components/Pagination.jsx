import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

function Pagination( props )
{
    let firstItem = () => {
        let firstIsActive = props.current === 1;
        return (
            <li 
                className={ firstIsActive ? "page-item disabled" : "page-item" }
                data-page={ !firstIsActive ? (props.current - 1) : "" }
                onClick={ props.onClick }
            >
                <span role="button" className="page-link" aria-label="Previous" aria-hidden="true">
                        <FontAwesomeIcon 
                            icon={ ["fas", "arrow-left"] } 
                            size="xs" />
                </span>
            </li>
        );
    };

    let items = () => {
        let pagination = [];

        for ( let i = 1; i <= props.last; i++ ) {
            let active = i === props.current;
            pagination.push(
                <li 
                    key={ i }
                    className={ active ? "page-item disabled" : "page-item" }
                    data-page={ i }
                    onClick={ props.onClick }
                >
                    <span role="button" className="page-link">
                        { i }
                    </span>
                </li>
            );
        }

        return pagination;
    };

    let lastItem = () => {
        let lastIsActive = props.current === props.last;
        return (
            <li 
                data-page={ !lastIsActive ? (props.current + 1) : "" }
                className={ lastIsActive ? "page-item disabled" : "page-item" }
                onClick={ props.onClick }
            >
                <span role="button" className="page-link" aria-label="Next" aria-hidden="true">
                    <FontAwesomeIcon 
                        icon={ ["fas", "arrow-right"] } 
                        size="xs" />
                </span>
            </li>
        );
    };

    return (
        <nav aria-label="Pagination">
            <ul className="pagination">
                {
                    firstItem()   
                }
                { 
                    items() 
                }
                {
                    lastItem()
                }
            </ul>
        </nav>
    );
}

export default Pagination;