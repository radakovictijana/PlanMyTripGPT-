import React from 'react'
import NavBar from '../components/NavBar'
import { adminLinks, userLinks, guideLinks } from '../components/CustomLinks'

function Home() {

  function getLinks() {
    const userRole = localStorage.getItem('user_role');
    if (userRole === "0") {
      return userLinks;
    }

    if (userRole === "1") {
      return adminLinks;
    }

    return guideLinks;
  }

  return (
    <>
      {/* <NavBar links={getLinks()}/> */}
      <div className= 'container-home'>
        
        <div className='home'>

        <h1>Welcome to CHATGPT application for generating travel plan!</h1>
      
        </div>
        

      </div>
    </>
  )
}

export default Home