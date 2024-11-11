let a = [17, 28, 30];
let b  = [99, 16, 8]

function compareTriplets(a, b) {
  let puntoalice = 0;
  let puntobob = 0;
  for (let i = 0; i < a.length; i++) {
    for (let j = 0; j < b.length; i++) {
      if (a[i] < b[j]) {
        puntoalice + 1;
      } else if (a[i] < b[j]) {
        puntobob + 1;
      } else if (a[i] === b[j]) {
        return;
      }
    }
  }
  console.log(puntoalice, puntobob);
}
