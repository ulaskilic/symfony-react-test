import React from "react";
import MaterialContainer from "@material-ui/core/Container";
import {makeStyles} from "@material-ui/core/styles";
import CircularProgress from "@material-ui/core/CircularProgress";
import Backdrop from "@material-ui/core/Backdrop";

const useStyles = makeStyles((theme) => ({
    container: {
        paddingLeft: theme.spacing(2),
        paddingTop: theme.spacing(2),
    },
}))

const Container = props => {
    const classes = useStyles();
    return (
        <>
            <MaterialContainer className={classes.container} maxWidth={"xl"}>
                <Backdrop open={props.isLoading} style={{zIndex: 999}}>
                    <CircularProgress color="inherit" />
                </Backdrop>
                {props.children}
            </MaterialContainer>
        </>
    )
}

export default Container;
