import {Button, Container, Image, Nav, Navbar} from "react-bootstrap";
import {Link} from "react-router-dom";
import React from "react";
import logo from '../../logo.svg';
import AutocompleteSearchBar from "./autocompleteSearchBar";

const Header = () => {
    return <Navbar expand={"lg"} className={"shadow-sm bg-body-tertiary z-3"}>
        <Container>
            <Link to={"/"}>
                <Navbar.Brand>
                    <Image src={logo} alt="logo" fluid/>
                </Navbar.Brand>
            </Link>
            <AutocompleteSearchBar/>
            <Nav>
                <Link to={"/"}>
                    <Button variant="link">
                        Home
                    </Button>
                </Link>
            </Nav>
        </Container>
    </Navbar>
}

export default Header;