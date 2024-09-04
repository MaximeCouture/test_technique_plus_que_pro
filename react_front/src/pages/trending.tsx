import {useQuery} from "react-query";
import {fetchTrendingData} from "../services/apiService";
import Loader from "../components/loader";
import Error from "../components/error";
import React, {useRef, useState} from "react";
import MoviePreview from "../components/movies/moviePreview";
import {Col, Row} from "react-bootstrap";
import {MovieType} from "../config/types";


const Trending = () => {

    const [trendingDay, setTrendingDay] = useState([]);
    const [trendingWeek, setTrendingWeek] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const containerRef = useRef(null);

    const fetchTrendingDay = async () => {
        let data = await fetchTrendingData(true, currentPage);
        return setTrendingDay(trendingDay.concat(data.data));
    }

    const fetchTrendingWeek = async () => {
        let data = await fetchTrendingData(false, currentPage);
        return setTrendingWeek(trendingWeek.concat(data.data));
    }

    const {isLoading: isLoadingDay, error: errorDay} = useQuery(['trending', 'day', currentPage], fetchTrendingDay);

    const {isLoading: isLoadingWeek, error: errorWeek} = useQuery(['trending', 'week', currentPage], fetchTrendingWeek);

    //onScroll, the currentPage will be increased, therefore triggering an automatic call to the next page of result in api
    //note that reactQuery has builtin infiniteScroll that is not used here
    const handleScroll = () => {
        if (containerRef.current) {
            const {scrollTop, scrollHeight, clientHeight } = containerRef.current
            if (scrollHeight - clientHeight - scrollTop < 500 && !isLoadingDay && !isLoadingWeek) {
                setCurrentPage(currentPage + 1);
            }
        }
    };

    if (errorDay || errorWeek) {
        return <Error/>;
    }

    return <div ref={containerRef} className={"overflow-y-scroll height-100"} onScroll={handleScroll}>
        <h1 className={"text-center"}>Welcome, here are some currently trending movies</h1>
        <Row>
            <Col md={6}>
                <h3 className={"text-center"}>Trending today</h3>
            </Col>
            <Col md={6}>
                <h3 className={"text-center"}>Trending this week</h3>
            </Col>
        </Row>
        <Row>
            <Col md={6}>
                {trendingDay && trendingDay.map((data: MovieType, key: number) => {
                    return <MoviePreview key={key} movie={data}/>
                })}
            </Col>
            <Col md={6}>
                {trendingWeek && trendingWeek.map((data: MovieType, key: number) => {
                    return <MoviePreview key={key} movie={data}/>
                })}
            </Col>
            {(isLoadingDay || isLoadingWeek) &&
                <Col xs={12}>
                    <Loader/>
                </Col>
            }
        </Row>
    </div>
}

export default Trending;