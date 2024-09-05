import React, {ReactElement} from 'react';
import './App.css';
import {QueryClient, QueryClientProvider} from 'react-query'
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Trending from "./pages/trending";
import Movie from "./pages/movie";
import Header from "./components/header/header";
import {Container} from "react-bootstrap";

//Queries are valid for 300 seconds prevent to much reloading
const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            staleTime: 1000,
            cacheTime: 1000,
        },
    },
});

const routes = [
    {
        path: "/",
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
                <Header />
                <Container className={"overflow-hidden screen-filler"}>
                    <Routes>
                        {routes.map((route: { path: string, element: ReactElement }, key: number) => {
                            return <Route path={route.path} element={route.element} key={key}/>
                        })}
                    </Routes>
                </Container>

            </BrowserRouter>
        </QueryClientProvider>
    );
}

export default App;
