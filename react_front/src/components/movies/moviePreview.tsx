import {memo} from "react";
import {useNavigate} from "react-router-dom";
import {MovieType} from "../../config/types";
import './moviePreview.scss';
import {Col, Row} from "react-bootstrap";

function truncateString(yourString: string, maxLength: number) {
    // get the index of space after maxLength
    const index = yourString.indexOf(" ", maxLength);
    return index === -1 ? yourString : yourString.substring(0, index)
}

interface MoviePreviewProps {
    movie: MovieType
}

const MoviePreview = (props: MoviePreviewProps) => {
    const {movie} = props;
    const navigate = useNavigate();

    const onPreviewClick = (movieId: number) => {
        navigate(`/movie/${movieId}`);
    }

    return <Row className={"movie_preview--container"} onClick={() => onPreviewClick(movie.id)}>
        <Col md={3} className={"movie_preview--thumbnail-container"}>
            <img
                src={`https://image.tmdb.org/t/p/original/${movie.poster_path}`}
                alt={movie.title}
                className={"movie_preview--thumbnail"}
            />
        </Col>
        <Col md={9} className={"movie_preview--info"}>
            <div className={"movie_preview--title"}>
                <h5>{movie.title}</h5>
            </div>
            <div className={"movie_preview--description"}>
                {truncateString(movie.overview, 100) + " ..."}
            </div>
        </Col>
    </Row>
}

export default memo(MoviePreview);