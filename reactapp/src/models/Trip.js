import React from 'react'
import { Link } from 'react-router-dom'


const Trip =({trip})=> {
  
  return (
    <>  
      <div className='card'>
         
        <div className='card-body'>
          <h3>{trip.destination}</h3>
          <p>Start date: {trip.start_date}</p>
          <p>End date: {trip.end_date} </p>
          {trip.picture?
           <img src={"http://127.0.0.1:8000"+trip.picture}className='card-img'></img>:<></>}
          <Link to={"/details/" + trip.id}>Details</Link>
           
        </div>
      </div>
    </>
  )
}

export default Trip