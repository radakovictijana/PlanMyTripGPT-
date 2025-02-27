import React from 'react'
import { useState,useEffect } from 'react'
import axios from 'axios';
import {Link,useNavigate} from 'react-router-dom'

function TravelsPage() {
const [travels,setTravels] = useState([]);
const [error,setError] = useState();
const[deleted,setDeleted]= useState();
const navigate = useNavigate();

useEffect(() => {
    const fetchTravels = async () => {
      const token = localStorage.getItem("auth_token");
      try {
        const response = await axios.get("api/travels", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        console.log(response.data);
        setTravels(response.data);
      } catch (err) {
        setError("Failed to fetch users.");
        
      }
    };

    fetchTravels();
  }, []);
function handleView(e,id){
  e.preventDefault();


}
 async function handleDelete(e, travelId){
    console.log('travelId is: ', travelId)
    e.preventDefault();
    const token = localStorage.getItem('auth_token');
    const response = await axios.delete('api/travels/' + travelId, {
    headers: {
      'Authorization': `Bearer ${token}`,
    }
  });

  setTravels(travels.filter((travel) => 
  travel.id !== travelId 
  ));
  }


  return (
    <div className='container'>
         
        <div className=''>
            <table className='table'>
            <thead>
                <tr>
                  <th>Travel ID</th>
                  <th>Destination</th>
                  <th>Start date</th>
                  <th>End date</th>
                  <th>User_Id</th>
                  <th>Details</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                {travels.length ? (travels.map((t,index)=>
                <tr key={t.id}>
                <td>{t.id}</td>
                <td>{t.destination}</td>
                <td>{t.start_date}</td>
                <td>{t.end_date}</td>
                <td>{t.user_id}</td>
                
                <td><Link to={`/details/${t.id}`}>View plans</Link>
                </td>
                <td><button type='button' onClick={(e)=>handleDelete(e,t.id)}>Delete travel</button></td>
                
                </tr>
                )) : (<tr>
                  No travel plans found!
                </tr>)}
              
              </tbody>
            </table>
        </div>

    </div>
  )
}

export default TravelsPage