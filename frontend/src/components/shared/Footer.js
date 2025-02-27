import React from "react";


const Footer =()=>{
    const currentYear = new Date().getFullYear();
    return (
    <div className="footer">
        <p>&copy; {currentYear} TravelApp</p>
        <p>made by Tijana Radakovic</p>
    </div>
    )
};

export default Footer;