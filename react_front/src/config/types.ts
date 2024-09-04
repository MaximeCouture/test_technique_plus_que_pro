export type MovieType =
    {
        "id": number,
        "title": string,
        "overview": string,
        "poster_path": string,
        "backdrop_path": string,
        "release_date": string | Date,
        "vote_average": number,
        "vote_count": number,
        "budget": number,
        "revenue": number,
        "tagline": string,
        "genres": {name: string}[]
    }