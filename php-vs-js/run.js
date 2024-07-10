let sum = 0
let numbers = Array.from({length: 10_000_000}, (_, i) => i + 1)
let start = Date.now()

//for (let number in numbers) {
//    sum += numbers[number]
//}

//for (let i = 0; i < 10_000_000; i++) {
//    sum += numbers[i]
//}

numbers.forEach((number) => {
    sum += number
})
let end = Date.now()
console.log(`SUM IS ${sum} AND IT TOOK ${end - start} milliseconds`)
