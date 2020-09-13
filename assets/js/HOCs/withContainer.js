import React from "react";
import Container from "../Components/Container/Container";

const withContainer = ChildElement => {
    return props => {
        const [loading, setLoading] = React.useState(false);
        return (
            <Container isLoading={loading}>
                <ChildElement setLoading={setLoading} isLoading={loading}>
                    {props.children}
                </ChildElement>
            </Container>
        )
    }
}

export default withContainer
