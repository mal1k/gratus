import React from "react";
import Chartjs from 'chart.js/auto';

class Chart extends React.Component
{
    constructor(props) 
    {
        super(props);

        this.state = {
            initchart: {},
        };

        this.node = React.createRef();

        this.handleClickOnWeek = this.handleClickOnWeek.bind(this);
        this.handleClickOnMonth = this.handleClickOnMonth.bind(this);
        this.handleClickOnYear = this. handleClickOnYear.bind(this);
    }

    getCookie( name )
    {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    /**
     * Initiate the Chart object
     * @param string timeType It may be: day-of-week, day-of-calendar, months
     * @param mixed range It may be a object: {begin: "", end: ""}
     */
    initializeChart(timeType, range)
    {
        const token = this.getCookie("atoken");
        const fullUrl = "/api/manager" + this.props.url;
        const node = this.node.current.id;

        if (this.state.initchart instanceof Chartjs) {
            this.state.initchart.destroy();
        }

        if (! (range instanceof Object)) return;

        let fetchParams = {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token,
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": document.querySelector("meta[name=csrf-token]").content
            },
            body: JSON.stringify({
                timeType: timeType, 
                range: range,
                model: this.props.model
            }),
        };

        fetch(fullUrl, fetchParams)
            .then(response => response.json())
            .then(data => {

                // posible options should be either an array or a string
                Chartjs.defaults.backgroundColor = [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ];

                // posible options should be either an array or a string
                // Chartjs.defaults.borderColor = [
                //     'rgba(255, 99, 132, 1)',
                //     'rgba(54, 162, 235, 1)',
                //     'rgba(255, 206, 86, 1)',
                //     'rgba(75, 192, 192, 1)',
                //     'rgba(153, 102, 255, 1)',
                //     'rgba(255, 159, 64, 1)'
                // ];

                Chartjs.defaults.responsive = true;
                
                Chartjs.defaults.elements.line.fill = true;

                Chartjs.defaults.elements.line.borderWidth = 1;

                // how strong the line should be wound
                Chartjs.defaults.elements.line.tension = 0.2;

                Chartjs.defaults.plugins.legend.position = "top";

                Chartjs.defaults.scale.beginAtZero = false;

                let chart = new Chartjs(node, {
                    type: this.props.type,
                    data: data,
                });
                
                this.setState({
                    initchart: chart
                });

            })
            .catch(error => console.error("There was an error!", error));
    }

    componentDidMount() 
    {
        this.initializeChart("week", {begin: 7, end: "today"});
    }

    handleClickOnWeek() 
    {
        this.initializeChart("week", {begin: 7, end: "today"});
    }

    handleClickOnMonth() 
    {
        this.initializeChart("day", {begin: 30, end: "today"});
    }

    handleClickOnYear() 
    {
        this.initializeChart("month", {begin: 12, end: "today"});
    }

    render() 
    {
        return (
            <>
            <canvas 
                id={ this.props.id }
                ref={ this.node }
                style={ this.props.style }></canvas>
            <div className="d-flex flex-row justify-content-end mt-3">
                {/* <button className="btn btn-sm btn-light">Day</button> */}
                <button className="btn btn-sm btn-light" onClick={ this.handleClickOnWeek }>
                    Week
                </button>
                <button className="btn btn-sm btn-light" onClick={ this.handleClickOnMonth }>
                    Month
                </button>
                <button className="btn btn-sm btn-light" onClick={ this.handleClickOnYear }>
                    Year
                </button>
            </div>
            </>
        );
    }
}

export default Chart;