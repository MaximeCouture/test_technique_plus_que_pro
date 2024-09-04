import {useEffect, useState} from "react";
import {fetchMovieDetails} from "../services/apiService";
import {useQuery} from "react-query";
import {useParams} from "react-router-dom";
import MovieDetails from "../components/movies/movieDetails";
import Loader from "../components/loader";
import Error from "../components/error";

const Movie = () => {

    const {movieId} = useParams();

    const [movie, setMovie] = useState()

    const fetchMovie = async (id: number) => {
        let data = await fetchMovieDetails(id);
        return setMovie(data.data);
    }


    const {isLoading, error} = useQuery(['movie', movieId], () => fetchMovie(parseInt(movieId || "")));


    if (isLoading) {
        return <Loader/>
    }

    if (error) {
        return <Error/>
    }

    return <>
        {movie && <MovieDetails movie={movie}/>}
    </>
}

export default Movie;