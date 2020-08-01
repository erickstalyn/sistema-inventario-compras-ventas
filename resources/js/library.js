//Comparador de objectos basicos
const compareObj = (a, b) => {
  const aKeys = Object.keys(a).sort();
  const bKeys = Object.keys(b).sort();
  if (aKeys.length !== bKeys.length) {
    return false;
  }
  if (aKeys.join("") !== bKeys.join("")) {
    return false;
  }
  for (let i = 0; i < aKeys.length; i++) {
    if (a[aKeys[i]] !== b[bKeys[i]]) {
      return false;
    }
  }
  return true;
};
