import React from 'react';

import Navbar from "./components/Navbar";
import Sidebar from "./components/Sidebar";
import Content from "./components/Content";
import Footer from "./components/Footer";

class App extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            isOverlayClicked: false
        };
    }

    render() {
        return (
            <React.StrictMode>
                <Navbar />
                <Sidebar />
                <Content />
                <Footer />
            </React.StrictMode>
        );
    }
}

export default App;