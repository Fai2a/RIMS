
const map = L.map('map').setView([31.47572583297416, 73.1384078043893], 8);

const googleLayer = L.gridLayer.googleMutant({
  maxZoom: 24,
  type: 'roadmap' // You can use 'roadmap', 'satellite', 'terrain', and 'hybrid'
}).addTo(map);

const markers = [
  {
    coords: [31.47476128265208, 73.13435972845004],
    title: "Saif Auto Workshop",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqcSE4xtR-TzynPxGxXgSQwIKcCVOPUzmdsQ&s",
    description: "Saif Auto Workshop offers comprehensive auto repair services with a focus on customer satisfaction. Our skilled mechanics handle everything from routine maintenance to major repairs.",
    services: [
      { name: "Oil Change", price: "1500 PKR", duration: "30 minutes" },
      { name: "Brake Service", price: "3000 PKR", duration: "45 minutes" },
      { name: "Tire Rotation", price: "1000 PKR", duration: "20 minutes" },
      { name: "Battery Check", price: "500 PKR", duration: "15 minutes" },
      { name: "Engine Tune-Up", price: "5000 PKR", duration: "1 hour" }
    ]
  },
  {
    coords: [31.4504, 73.1350],
    title: "Ali Car Service",
    img: "https://content.jdmagicbox.com/comp/villupuram/y9/9999p4146.4146.200229142652.l8y9/catalogue/v-b-srs-hyundai-viluppuram-villupuram-car-dealers-lte89td189.jpg",
    description: "Ali Car Service is known for its quick and efficient service. We specialize in oil changes, brake repairs, and engine diagnostics.",
    services: [
      { name: "Brake Service", price: "2500 PKR", duration: "40 minutes" },
      { name: "Tire Rotation", price: "1200 PKR", duration: "25 minutes" },
      { name: "Engine Tune-Up", price: "4500 PKR", duration: "1 hour" },
      { name: "Transmission Service", price: "6000 PKR", duration: "2 hours" },
      { name: "Fluid Checks", price: "800 PKR", duration: "15 minutes" }
    ]
  },
  {
    coords: [31.4944, 73.0994],
    title: "Khan Auto Repair",
    img: "https://fcache1.pakwheels.com/original/4X/d/a/b/dabf499201ccf01744e04f8e93f1e4aab790b962.jpg",
    description: "Khan Auto Repair provides top-notch repair services at affordable prices. Our team is experienced in handling all types of vehicle issues.",
    services: [
      { name: "Battery Check", price: "700 PKR", duration: "15 minutes" },
      { name: "Fluid Checks", price: "900 PKR", duration: "10 minutes" },
      { name: "Air Filter Replacement", price: "1500 PKR", duration: "25 minutes" },
      { name: "Wheel Alignment", price: "3500 PKR", duration: "1 hour" },
      { name: "AC Service", price: "4000 PKR", duration: "45 minutes" }
    ]
  },
  {
    coords: [31.4832, 73.1258],
    title: "Expert Auto Care",
    img: "https://fcache1.pakwheels.com/original/4X/1/0/1/1013eaa227647fd38ab371717dc3a69669fab442.jpg",
    description: "Expert Auto Care offers a full range of auto services including tire changes, battery replacement, and transmission repairs. Customer satisfaction is our priority.",
    services: [
      {name:"Oil Change", price:"800 PKR", duration:"20 minutes"},
      {name: "Brake Service", price:"1000 PKR" ,duration: "30 minutes"},
      {name:  "Tire Rotation" , price:"1200 PKR", duration:"20 minutes"} ,
      {name: "Battery Check", price:"600 PKR" ,duration:"25 minutes"},
      {name:  "Transmission Service", price:"900 PKR" ,duration:"45 minutes"}
    ]
  },
  {
    coords: [31.4561, 73.1230],
    title: "Quick Fix Garage",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRlOPN9psiYYhRLbWtHGGTHa3u5QQX-iZa4u3x1fTO77O-ohEDwzOwEyPujBarx-MzNMvg&usqp=CAU",
    description: "Quick Fix Garage is dedicated to providing fast and reliable auto repairs. Our mechanics are trained to handle a wide variety of car issues.",
    services: [
      {name:"Engine Tune-Up", price:"700 PKR", duration:"25 minutes"} ,
      {name:"Transmission Service", price:"1000 PKR", duration:"30 minutes"},
      {name:"Fluid Checks", price:"500 PKR", duration:"35 minutes"}, 
      {name:"Air Filter Replacement", price:"600 PKR", duration:"45 minutes"},
      {name: "Wheel Alignment", price:"700 PKR", duration:"30 minutes"},
    ]
  },
  {
    coords: [31.4706, 73.1402],
    title: "Reliable Mechanics",
    img: "https://www.jwmotors.com.pk/images/portfolio/portfolio8.jpg",
    description: "Reliable Mechanics offers expert repair services for all makes and models. We pride ourselves on our honesty and professionalism.",
    services: [
    {name:"Brake Service", price:"1500 PKR", duration:"30 minutes"},
    {name:"Tire Rotation", price:"500 PKR",duration:"35 minutes"},
    {name:"Battery Check", price:"700 PKR", duration: "40 minutes"},
    {name:"Engine Tune-Up", price:"500 PKR", duration:"60 minutes"},
    {name:"AC Service", price:"1000 PKR" , duration:"45 minutes"},
    ]
  },
  {
    coords: [31.4615, 73.1294],
    title: "City Auto Service",
    img: "https://www.fixmycar.pk/wp-content/uploads/2022/12/Best-Hybrid-Honda-Toyota-Auto-Workshop-and-Car-Mechanic-in-Islamabad-and-Near-Rawalpindi.jpg",
    description: "City Auto Service is your one-stop shop for all your auto repair needs. From oil changes to engine repairs, we do it all with precision and care.",
    services: [
      {name:"Oil Change", price:"500 PKR", duration:"25 minutes" },
      {name:"Transmission Service", price:"35 minutes", duration:"30 minutes"},
      {name:"Fluid Checks", price:"700 PKR", duration:"35 minutes" },
      {name:"Air Filter Replacement", price:"800 PKR" , duration:"30 minutes"},
      {name: "Wheel Alignment", price:"900 PKR", duation:"35 minutes"},
    ]
  },
  {
    coords: [31.4758, 73.1104],
    title: "Auto Master Workshop",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzgBgkNyVW0WXS6PThp7K_KFsS7JzRrJFcCw&s",
    description: "Auto Master Workshop provides high-quality auto repair services. Our experienced mechanics ensure that your vehicle is in top condition.",
    services: [
      { name: "Tire Rotation", price: "1200 PKR", duration: "25 minutes" },
      { name: "Battery Check", price: "800 PKR", duration: "20 minutes" },
      { name: "Engine Tune-Up", price: "5000 PKR", duration: "1 hour" },
      { name: "AC Service", price: "4000 PKR", duration: "45 minutes" },
      { name: "Transmission Service", price: "6000 PKR", duration: "1 hour 30 minutes" }
    ]
  },
  {
    coords: [31.4403, 73.1224],
    title: "Professional Auto Repairs",
    img: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTg3s72Ehl-25dIUStoH2mgF1fnanL62YNmTptPs0mCohhJmr_Mzze8XxkwyWyzwWbp_sA&usqp=CAU",
    description: "Professional Auto Repairs offers reliable and affordable auto repair services. We use the latest tools and technology to diagnose and fix your vehicle.",
    services: [
      { name: "Oil Change", price: "1500 PKR", duration: "30 minutes" },
      { name: "Brake Service", price: "3000 PKR", duration: "45 minutes" },
      { name: "Fluid Checks", price: "1000 PKR", duration: "20 minutes" },
      { name: "Air Filter Replacement", price: "1200 PKR", duration: "25 minutes" },
      { name: "Wheel Alignment", price: "2500 PKR", duration: "40 minutes" }
    ]
  },
  {
    coords: [31.4486, 73.1372],
    title: "Auto Tech Solutions",
    img: "https://content.jdmagicbox.com/comp/indore/y8/0731px731.x731.160314191549.a4y8/catalogue/car-solution-automobiles-vijay-nagar-indore-car-repair-and-services-wg77c-250.jpg",
    description: "Auto Tech Solutions specializes in modern auto repair techniques. Our skilled technicians provide efficient and effective solutions for all your car problems.",
    services: [
      { name: "Engine Tune-Up", price: "5500 PKR", duration: "1 hour 15 minutes" },
      { name: "Transmission Service", price: "6000 PKR", duration: "1 hour 30 minutes" },
      { name: "Fluid Checks", price: "1000 PKR", duration: "20 minutes" },
      { name: "AC Service", price: "4000 PKR", duration: "45 minutes" },
      { name: "Battery Check", price: "800 PKR", duration: "20 minutes" }
    ]
  },
  {
    coords: [31.4707, 73.1168],
    title: "Affordable Auto Care",
    img: "https://s3-media0.fl.yelpcdn.com/bphoto/pZx421nDPZ_4GnwY4lb4cw/180s.jpg",
    description: "Affordable Auto Care is dedicated to providing top-quality auto repairs at competitive prices. We offer a range of services to keep your car running smoothly.",
    services: [
      { name: "Brake Service", price: "3000 PKR", duration: "45 minutes" },
      { name: "Tire Rotation", price: "1200 PKR", duration: "25 minutes" },
      { name: "Air Filter Replacement", price: "1500 PKR", duration: "30 minutes" },
      { name: "Wheel Alignment", price: "2500 PKR", duration: "40 minutes" },
      { name: "AC Service", price: "4000 PKR", duration: "45 minutes" }
    ]
  },
  {
    coords: [31.4847, 73.1456],
    title: "Auto Excellence",
    img: "https://s3-media0.fl.yelpcdn.com/bphoto/pZx421nDPZ_4GnwY4lb4cw/180s.jpg",
    description: "Auto Excellence offers premium auto repair services. Our experienced mechanics ensure that your vehicle receives the best care possible.",
    services: [
      { name: "Oil Change", price: "1600 PKR", duration: "30 minutes" },
      { name: "Transmission Service", price: "6000 PKR", duration: "1 hour 30 minutes" },
      { name: "Fluid Checks", price: "1000 PKR", duration: "20 minutes" },
      { name: "Air Filter Replacement", price: "1500 PKR", duration: "30 minutes" },
      { name: "Battery Check", price: "800 PKR", duration: "20 minutes" }
    ]
  },
  {
    coords: [31.4607, 73.1078],
    title: "Super Auto Workshop",
    img: "https://s3-media0.fl.yelpcdn.com/bphoto/85IIOtkWm0S9P5IeCtDQKA/300s.jpg",
    description: "Super Auto Workshop provides a wide range of auto repair services. Our team is committed to delivering high-quality service to keep your car in top condition.",
    services: [
      { name: "Brake Service", price: "3000 PKR", duration: "45 minutes" },
      { name: "Tire Rotation", price: "1200 PKR", duration: "25 minutes" },
      { name: "Battery Check", price: "800 PKR", duration: "20 minutes" },
      { name: "Engine Tune-Up", price: "5000 PKR", duration: "1 hour" },
      { name: "Wheel Alignment", price: "2500 PKR", duration: "40 minutes" }
    ]
  },
  {
    coords: [31.4702, 73.1254],
    title: "Prime Auto Service",
    img: "https://s3-media0.fl.yelpcdn.com/bphoto/97ebbZjG_WmRbfmZBsuTdw/300s.jpg",
    description: "Prime Auto Service offers reliable and affordable auto repair solutions. Our experienced mechanics handle everything from minor repairs to major overhauls.",
    services: [
      { name: "Oil Change", price: "1500 PKR", duration: "30 minutes" },
      { name: "Transmission Service", price: "6000 PKR", duration: "1 hour 30 minutes" },
      { name: "Fluid Checks", price: "1000 PKR", duration: "20 minutes" },
      { name: "AC Service", price: "4000 PKR", duration: "45 minutes" },
      { name: "Brake Service", price: "3000 PKR", duration: "45 minutes" }
    ]
  }, 
 
  {
    coords: [31.4448, 73.1197],
    title: "Trusted Auto Care",
    img: "https://s3-media0.fl.yelpcdn.com/bphoto/tZOwcCB4FDicuTkvr6mv6A/1000s.jpg",
    description: "Trusted Auto Care provides expert auto repair services. We are committed to ensuring that your vehicle is safe and reliable.",
    services: [
      { name: "Tire Rotation", price: "1200 PKR", duration: "25 minutes" },
      { name: "Battery Check", price: "800 PKR", duration: "20 minutes" },
      { name: "Engine Tune-Up", price: "5000 PKR", duration: "1 hour" },
      { name: "Air Filter Replacement", price: "1200 PKR", duration: "25 minutes" },
      { name: "Transmission Service", price: "6000 PKR", duration: "1 hour 30 minutes" }
    ]
  },
  {
    coords: [31.4527, 73.1394],
    title: "Mechanics Hub",
    img: "https://s3-media0.fl.yelpcdn.com/bphoto/PobC8EDlW3Pi-BC4Q77oQw/1000s.jpg",
    description: "Mechanics Hub offers comprehensive auto repair services. Our skilled technicians are dedicated to providing the highest quality service.",
      services: [
      { name: "Brake Service", price: "3000 PKR", duration: "45 minutes" },
      { name: "Tire Rotation", price: "1200 PKR", duration: "25 minutes" },
      { name: "Battery Check", price: "800 PKR", duration: "20 minutes" },
      { name: "Engine Tune-Up", price: "5000 PKR", duration: "1 hour" },
      { name: "Wheel Alignment", price: "2500 PKR", duration: "40 minutes" }
    ]

  }
];

const popupContentTemplate = (title, img, description) => 
  `<div class="popup-content">
    <h1>${title}</h1>
    <img src="${img}" alt="${title}">
    <p>${description}</p>
    <div class="rating">
      <span>&#9733;</span>
      <span>&#9733;</span>
      <span>&#9733;</span>
      <span>&#9733;</span>
      <span>&#9734;</span>
    </div>
    <a href="mechanic_profile.php" class="appointment-btn">Book Appointment</a>
  </div>
`;

markers.forEach(marker => {
  const markerInstance = L.marker(marker.coords, { title: marker.title })
    .bindPopup(popupContentTemplate(marker.title, marker.img, marker.description))
    .addTo(map);

  markerInstance.data = marker;
});

map.on('popupopen', function (e) {
  const popupNode = e.popup._contentNode;
  const markerInstance = e.popup._source;

  const appointmentButton = popupNode.querySelector('.appointment-btn');

  if (appointmentButton) {
    appointmentButton.addEventListener('click', function (event) {
      event.preventDefault(); // prevent default anchor behavior
      
      // profile.html 
      const profileUrl = new URL('/rims/mechanic_profile.php', window.location.origin);
      profileUrl.searchParams.set('title', markerInstance.data.title);
      profileUrl.searchParams.set('img', markerInstance.data.img);
      profileUrl.searchParams.set('description', markerInstance.data.description);

      // Redirect to the profile page
      window.location.href = profileUrl.toString();
    });
  }
});