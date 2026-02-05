import { useState, useEffect } from "react"
import fetchApi from "../fetchApi";



export default function Me() {
    const [data, setData] = useState<{vorname: string, nachname: string}>(null);
    useEffect(() => {
        fetchApi(`/user`)
            .then((jsonData: any) => {
                console.log(jsonData);
                setData(jsonData);
            })
            .catch((error) => {
                console.error('Fetch error:', error);
            });
    }, [])

    if(data == null) {
        return <div>Logged Out</div>
    }

    return (
        <>{data.vorname} {data.nachname}</>
    )
}
