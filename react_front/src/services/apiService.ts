import axiosService from "./axiosService";


const getTrendingMovieURI = process.env.REACT_APP_API_GET_TRENDING_MOVIE_URI || '';
const geMovieDetailsURI = process.env.REACT_APP_API_GET_MOVIE_DETAILS_URI || '';

export const fetchTrendingData = (daily: boolean) => {
    const uri = getTrendingMovieURI + "?daily=" + daily ?? "0";
    return axiosService.get(uri);
}

export const fetchMovieDetails = (id: number) => {
    const uri = geMovieDetailsURI.replace("__ID__", id.toString());
    return axiosService.get(uri);
}
