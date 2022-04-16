function TableHead( props )
{
    let tableHead = () => {
        let output = [];

        if (
            Array.isArray( props.items ) &&
            props.items.length > 0
        ) {

            let i, item = null;

            props.items.map((value, index) => {
                item = (
                    <th key={ index }>
                        { value }
                    </th>
                );
                output.push(item);
                i++;
            });

            output.push(<th key={ i }></th>);
        }

        return ( <tr>{ output }</tr> );
    };

    return (
        <thead>
            { tableHead() }
        </thead>
    );
}

export default TableHead;