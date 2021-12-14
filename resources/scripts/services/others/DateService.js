import dayJs from "dayjs";

const formatDate =  (date)=>{
    const formattedDate   =  dayJs(date, 'YYYY-MM-DD');
    return formattedDate.isValid() ? formattedDate.format('DD/MM/YYYY'): null;
}

export { formatDate }