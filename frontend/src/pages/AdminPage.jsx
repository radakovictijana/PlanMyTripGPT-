import React from 'react'
import {Link} from 'react-router-dom'

function AdminPage() {
  return (
    <div className='container'>
       <div className='navbar'>
            <ul className='nav-list'>
                <li className='nav-item'><Link to= "/users">Users</Link></li>
                <li className='nav-item'><Link to= "/travels">Travels</Link></li>
                <li className='nav-item'><Link to= "/plans">Plans</Link></li>
            </ul>
        </div>


    </div>
  )
}

export default AdminPage