import React from "react";
import ReactDOM from "react-dom";
import "./Assets/scss/app.scss";
import Router from "./Router";
import {BrowserRouter} from "react-router-dom";
import {CssBaseline, ThemeProvider} from "@material-ui/core";
import DefaultTheme from './Themes/Default';
import AppBar from "@material-ui/core/AppBar";
import Toolbar from "@material-ui/core/Toolbar";
import Typography from "@material-ui/core/Typography";

const App = props => {
    return (
        <BrowserRouter>
            <ThemeProvider theme={DefaultTheme}>
                <CssBaseline/>
                <AppBar position="static">
                    <Toolbar>
                        <Typography variant="h6">
                            Task Manager
                        </Typography>
                    </Toolbar>
                </AppBar>
                <Router/>
            </ThemeProvider>
        </BrowserRouter>
    )
}

console.log('okl');
ReactDOM.render(
    <App/>,
    document.getElementById('app')
);