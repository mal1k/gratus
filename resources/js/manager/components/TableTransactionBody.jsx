function TableBody( props )
{
    let tableBodyRow = ( item ) => {
        let i = 0;
        let output = [];
        for (let prop in item) {
            if ( item.hasOwnProperty( prop ) ) {
                output.push(
                    <th key={ i++ }>
                        { item[prop] }
                    </th>
                );
            }
        }
        output.push();
        return output;
    };

    return (
        <tbody>
            {
                props.items ? props.items.map((value, index) => {
                    return (
                        <tr key={ index }>
                            { tableBodyRow(value) }
                        </tr>
                    );
                }) : "Content is loading..."
            }
        </tbody>
    );
}

export default TableBody;
