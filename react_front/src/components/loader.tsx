import loader from '../loader.gif';
import {Image} from "react-bootstrap";

const Loader = () => {
    return <Image src={loader} className={"loader"} fluid />
}

export default Loader;