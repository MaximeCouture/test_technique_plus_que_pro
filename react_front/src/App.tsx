import React, {ReactElement} from 'react';
import './App.css';
import {QueryClient, QueryClientProvider} from 'react-query'
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Home from "./pages/home";
import Trending from "./pages/trending";
import Movie from "./pages/movie";
import Header from "./components/header/header";
import {Container} from "react-bootstrap";

const queryClient = new QueryClient();

const routes = [
    {
        path: "/",
        element: <Home/>
    },
    {
        path: "/trending",
        element: <Trending/>
    },
    {
        path: "/movie/:movieId",
        element: <Movie/>
    }
]

function App() {
    return (
        <QueryClientProvider client={queryClient}>
            <BrowserRouter>
                <Header/>
                <Container>
                    <Routes>
                        {routes.map((route: { path: string, element: ReactElement }) => {
                            return <Route path={route.path} element={route.element}/>
                        })}
                        <Route path="/" element={<Home/>}/>
                    </Routes>
                </Container>
            </BrowserRouter>
        </QueryClientProvider>
    );
}

export default App;
