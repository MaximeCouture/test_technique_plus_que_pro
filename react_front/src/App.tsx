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
            staleTime: 300,
            cacheTime: 300,
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
                <Container className={"overflow-hidden screen-filler"}>
                    <Header />
                    <Routes>
                        {routes.map((route: { path: string, element: ReactElement }) => {
                            return <Route path={route.path} element={route.element}/>
                        })}
                    </Routes>
                </Container>

            </BrowserRouter>
        </QueryClientProvider>
    );
}

export default App;
