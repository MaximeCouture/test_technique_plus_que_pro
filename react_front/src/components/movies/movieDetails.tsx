import {MovieType} from "../../config/types";
import {memo} from "react";
import {Col, Row} from "react-bootstrap";
import './movieDetails.scss';

interface MovieDetailsProps {
    movie: MovieType
}

const MovieDetails = (props: MovieDetailsProps) => {

    const {movie} = props;

    return <>
        <Row className={"movie_details--container"}>
            <Col md={4} className={"movie_details--thumbnail-container"}>
                <img
                    src={`https://image.tmdb.org/t/p/original/${movie.poster_path}`}
                    alt={movie.title}
                    className={"movie_details--thumbnail"}
                />
            </Col>
            <Col md={8} className={"movie_details--info"}>
                <div className={"movie_details--title"}>
                    {movie.title}
                </div>
                {movie.tagline && <div className={"movie_details--tagline"}>
                    {movie.tagline}
                </div>}
                <div className={"movie_details--budget"}>
                    Budget : {movie.budget.toLocaleString()}$
                </div>
                <div className={"movie_details--budget"}>
                    Note : {movie.vote_average} sur {movie.vote_count} vote(s)
                </div>
                <div className={"movie_details--budget"}>
                    Genre(s) : {movie.genres.map((genre: {name: string}) => <span>{genre.name} </span>)}
                </div>
            </Col>
            </Row>
    </>
}

export default memo(MovieDetails)