import {useQuery} from "react-query";
import {fetchTrendingData} from "../services/apiService";
import Loader from "../components/loader";
import Error from "../components/error";
import React, {useState} from "react";
import MoviePreview from "../components/movies/moviePreview";
import {Col, Row} from "react-bootstrap";


const Trending = () => {

    const [trendingDay, setTrendingDay] = useState([]);
    const [trendingWeek, setTrendingWeek] = useState([]);

    const fetchTrendingDay = async () => {
        let data = await fetchTrendingData(true);
        return setTrendingDay(data.data);
    }

    const fetchTrendingWeek = async () => {
        let data = await fetchTrendingData(false);
        return setTrendingWeek(data.data);
    }

    const {isLoading: isLoadingDay, error: errorDay} = useQuery(['trending', 'day'], fetchTrendingDay);

    const {isLoading: isLoadingWeek, error: errorWeek} = useQuery(['trending', 'week'], fetchTrendingWeek);

    if (isLoadingDay || isLoadingWeek) {
        return <Loader/>;
    }

    if (errorDay || errorWeek) {
        return <Error />;
    }

    return <>
        <Row>
            <Col md={6}>
                Trending today
            </Col>
            <Col md={6}>
                Trending this week
            </Col>
        </Row>
    <Row>
        <Col md={6}>
            {trendingDay && trendingDay.map((data) => {
                return <MoviePreview movie={data} />
            })}
        </Col>
        <Col md={6}>
            {trendingWeek && trendingWeek.map((data) => {
                return <MoviePreview movie={data} />
            })}
        </Col>
    </Row>
    </>
}

export default Trending;