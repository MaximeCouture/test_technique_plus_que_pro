import {Container, Image, Nav, Navbar} from "react-bootstrap";
import {Link} from "react-router-dom";
import React from "react";
import logo from '../../logo.svg';
import AutocompleteSearchBar from "./autocompleteSearchBar";

const Header = () => {
    return <Navbar expand={"lg"} >
        <Container>
            <Link to={"/"}>
                <Navbar.Brand>
                    <Image src={logo} alt="logo" fluid/>
                </Navbar.Brand>
            </Link>
            <AutocompleteSearchBar />
            <Nav>
                <Nav.Link>
                    <Link to={"/"}>
                        Home
                    </Link>
                </Nav.Link>
                <Nav.Link>
                    <Link to={"/trending"}>
                            Trending
                    </Link>
                </Nav.Link>
            </Nav>
        </Container>
    </Navbar>
}

export default Header;