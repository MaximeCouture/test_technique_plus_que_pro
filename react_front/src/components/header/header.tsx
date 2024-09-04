import {Container, Image, Nav, Navbar} from "react-bootstrap";
import {Link} from "react-router-dom";
import React from "react";
import logo from '../../logo.svg';

const Header = () => {
    return <Navbar expand={"lg"}>
        <Container>
            <Link to={"/"}>
                <Navbar.Brand>
                    <Image src={logo} alt="logo" fluid/>
                </Navbar.Brand>
            </Link>
            <Nav>
                <Link to={"/"}>
                    Home
                </Link>
                <Link to={"/trending"}>
                    Trending
                </Link>
            </Nav>
        </Container>
    </Navbar>
}

export default Header;