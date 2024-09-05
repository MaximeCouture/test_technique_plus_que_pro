import {Badge, Dropdown, Form} from "react-bootstrap";
import React, {useState} from "react";
import {useDebounce} from "@uidotdev/usehooks";
import {fetchAutocompleteSearchResults} from "../../services/apiService";
import {useQuery} from "react-query";
import {MovieType} from "../../config/types";
import Loader from "../loader";
import {useNavigate} from "react-router-dom";


const AutocompleteSearchBar = () => {

    const [keywords, setKeywords] = useState("");
    const [searchParams] = useDebounce([keywords], 300)
    const [searchResults, setSearchResults] = useState([]);
    const [showResults, setShowResults] = useState(false);
    const navigate = useNavigate();

    const fetchSearchResults = async (term: string) => {
        let data = await fetchAutocompleteSearchResults(term);
        return setSearchResults(data?.data);
    }

    const onResultClick = (movieId: number) => {
        navigate(`/movie/${movieId}`);
        setKeywords("");
        setSearchResults([]);
    }

    const {isLoading} = useQuery(['autocomplete', searchParams], () => fetchSearchResults(searchParams), {enabled: !!searchParams});


    return <div>
        <Form.Control
            type={"text"}
            value={keywords}
            id={"autocompleteSearch"}
            placeholder={"search movie or genre"}
            onChange={(event) => setKeywords(event.target.value)}
            onFocus={() => setShowResults(true)}
            //timeout prevent dropdown to close before click on item
            onBlur={() => setShowResults(false)}
        />
        {showResults && searchParams && (searchResults || isLoading) &&
            <div className={"autocomplete_search--results"}>
                <Dropdown.Menu className={"no-left shadow-lg"} show={!!searchResults}>
                    {!!searchResults && !isLoading && searchResults.map((movie: MovieType, key: number) => {
                        return <Dropdown.Item onMouseDown={() => onResultClick(movie.id)} key={key}>
                            <h6 className={"d-inline"}>{movie.title}</h6>
                            <div className={"badges justify-content-end"}>
                                {movie.genres.map((genre: {name: string}, key: number) => {
                                    return <Badge pill bg={"secondary"} key={key}>{genre.name}</Badge>
                                })}
                            </div>
                        </Dropdown.Item>
                    })}
                    {isLoading && <Loader/>}
                </Dropdown.Menu>
            </div>
        }
    </div>
}

export default AutocompleteSearchBar