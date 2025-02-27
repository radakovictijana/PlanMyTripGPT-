import './App.css';
import Home from './pages/Home';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from './pages/Login';
import Register from './pages/Register';
import Trips from './trips/trips.json';
import Users from './trips/users.json';
import Details from './components/shared/Details';
import 'bootstrap/dist/css/bootstrap.min.css';
import TripPage from './pages/TripPage';
import ChatPage from './pages/ChatPage';
import AdminPage from './pages/AdminPage';
import UsersPage from './pages/adminpage/UsersPage';
import TravelsPage from './pages/adminpage/TravelsPage';
import DetailsForAdmin from './pages/adminpage/TravelsPage';
import NavBar from './components/NavBar';
import { userLinks, adminLinks, guideLinks } from './components/CustomLinks';
import { useState, createContext, useContext } from 'react';


function App() {
  const [token, setToken] = useState(null);
  
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


  function addToken(auth_token) {
    setToken(auth_token);
    sessionStorage.setItem('access_token', auth_token);
  }

  return (
    <>

      <BrowserRouter>
        

        <Routes>
        <Route path="/register" element={<Register />}/>
        <Route path="/login" element={<Login addToken={addToken}/>}/>

            <Route path='/' element={<NavBar links={getLinks()} token={token}></NavBar>}>
              <Route path="/home" element={<Home  />}/>
              <Route path= "/trips"element={<TripPage/>}/>
              <Route path="/details/:id" element={<Details/>}/>           
              <Route path="/chatGpt" element={<ChatPage/>}/>           
              <Route path="/admin" element={<AdminPage/>}/>           
              <Route path="/users" element={<UsersPage/>}/>           
              <Route path="/travels" element={<TravelsPage/>}/> 
            </Route>
                    
        </Routes>
      </BrowserRouter>
    </>
  );
}

export default App;
