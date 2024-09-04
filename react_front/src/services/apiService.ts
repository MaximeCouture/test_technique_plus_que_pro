import axiosService from "./axiosService";


const getTrendingMovieURI = process.env.REACT_APP_API_GET_TRENDING_MOVIE_URI || '';
const geMovieDetailsURI = process.env.REACT_APP_API_GET_MOVIE_DETAILS_URI || '';
const geAutocompleteSearchURI = process.env.REACT_APP_API_GET_AUTOCOMPLETE_SEARCH_URI || '';

export const fetchTrendingData = (daily: boolean, page: number) => {
    const uri = getTrendingMovieURI + "?daily=" + (daily ? "1" : "0") + "&page=" + page;
    return axiosService.get(uri);
}

export const fetchMovieDetails = (id: number) => {
    const uri = geMovieDetailsURI.replace("__ID__", id.toString());
    return axiosService.get(uri);
}

export const fetchAutocompleteSearchResults = (term: string) => {
    if (term.length >= 3) {
        const uri = geAutocompleteSearchURI + "?term=" + term;
        return axiosService.get(uri);
    }
}