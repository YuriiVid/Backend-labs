function getWaterMetersFromAPI(){
    fetch('http://localhost/labs/lab4/lab4.php')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        let content=``;
        for(i=0;i<data.length;i++){
            content+=`
                <b>`+data[i].name+`</b></br>
                Ціна: `+data[i].price+` </br>
                Виробник: `+data[i].manufacturer+`</br>
                Характеристики:</br>
            `;
            for (const [key, value] of Object.entries(data[i].properties)) {
                content+=key+`: `+value+`</br>`;
            }
        }
        document.getElementById('WaterMetersContainer').innerHTML=content;
    });
}