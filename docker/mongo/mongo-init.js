db.createUser(
  {
    user: "user",
    pwd: "secret",
    roles: [
      {role: "readWrite", db: "articles"},
      {role: "readWrite", db: "test-articles"}
    ]
  }
);

db.system.js.save({
  _id: "dictionary_diff",
  value: function (targetTokensCount, targetTokensLength, sourceTokensCount, sourceTokensLength, diffLimit) {
    let diffLength = Math.abs(targetTokensLength - sourceTokensLength);

    for (let word in sourceTokensCount) {
      if (targetTokensCount[word] === undefined) {
        diffLength += sourceTokensCount[word];
      } else {
        diffLength += Math.abs(targetTokensCount[word] - sourceTokensCount[word]);
      }

      if (diffLength > diffLimit) {
        return false;
      }
    }

    return true;
  }
});
