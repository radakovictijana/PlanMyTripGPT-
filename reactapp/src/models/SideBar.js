import React from 'react'

function SideBar({favorites,numFav}) {
  return (
    <div className = 'sidebar'>

        <h4>Favorites</h4>
        <h6>{numFav}</h6>
        <div className='favorite-list'></div>
            {favorites.map((trip,index)=>{
                return ( 
                    <div className="trip-card" key={trip.id}>
                      <img src={require (".././trips/img/" +trip.picture)} alt={trip.naziv} /> 
                      <p>{trip.naziv}</p>
                      </div>   
                )
            })}
    </div>
  )
}

export default SideBar