import React from 'react'

const Activity=({activities})=>{
  
    return (
        <>
          <div className='card-ac'>
            <div className='card-body-ac'>
              <p>{activities.naziv_aktivnosti}</p>          
            </div>
  
          </div>
        </>
    )
}
export default Activity
