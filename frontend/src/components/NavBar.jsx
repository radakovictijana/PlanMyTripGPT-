import React, { useEffect } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useState, createContext, useContext } from 'react';
import { Outlet } from 'react-router-dom';
import axios from 'axios';
function NavBar({ links, token }) {
  const [currentToken, setCurrentToken] = useState(token);
  const navigate = useNavigate();

  useEffect(() => {
    const tokenFromStorage = sessionStorage.getItem('access_token');
    setCurrentToken(tokenFromStorage);
  }, [token]);

  function handleLogout() {
    let config = {
      method: "post",
      url: "api/logout",
      headers: {
        Authorization: "Bearer " + token,
      },
    };

    axios
      .request(config)
      .then((response) => {
        console.log(JSON.stringify(response.data));
        localStorage.clear();
      })
      .catch((error) => {
        console.log(error);
      });
    navigate('/login');
  }

  return (
    <div>
      <nav className="navbar">
      <ul className="nav-list">
        {currentToken && links.map((link) => (
          <li key={link.to} className="nav-item">
            <a href={link.to}>{link.text}</a>
          </li>
        ))}

        
        {currentToken ? (
          <li className="nav-item">
            <button onClick={handleLogout}>Logout</button>
          </li>
        ) : (
          <li className="nav-item">
            <a href="/login">Login</a>
          </li>
        )}
      </ul>
    </nav>

      <Outlet />  
    </div>
    
  );
}

export default NavBar;
