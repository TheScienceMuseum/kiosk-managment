import React, { Component } from "react";
import {BrowserRouter, Switch} from "react-router-dom";

export default class App extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <BrowserRouter>
                <Switch>

                </Switch>
            </BrowserRouter>
        );
    }
}
